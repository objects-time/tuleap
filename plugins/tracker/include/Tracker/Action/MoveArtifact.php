<?php
/**
 * Copyright (c) Enalean, 2018. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tuleap\Tracker\Action;

use PFUser;
use SimpleXMLElement;
use Tracker;
use Tracker_Artifact;
use Tracker_Artifact_PriorityManager;
use Tracker_Artifact_XMLImport;
use Tracker_XML_Exporter_ArtifactXMLExporter;
use Tuleap\Tracker\Artifact\ArtifactsDeletion\ArtifactsDeletionManager;
use Tuleap\Tracker\Exception\MoveArtifactNotDoneException;
use Tuleap\Tracker\Exception\MoveArtifactSemanticsException;
use Tuleap\Tracker\Exception\MoveArtifactSemanticsMissingException;
use Tuleap\Tracker\XML\Updater\MoveChangesetXMLUpdater;

class MoveArtifact
{

    /**
     * @var ArtifactsDeletionManager
     */
    private $artifacts_deletion_manager;

    /**
     * @var Tracker_XML_Exporter_ArtifactXMLExporter
     */
    private $xml_exporter;

    /**
     * @var MoveChangesetXMLUpdater
     */
    private $xml_updater;

    /**
     * @var Tracker_Artifact_XMLImport
     */
    private $xml_import;

    /**
     * @var Tracker_Artifact_PriorityManager
     */
    private $artifact_priority_manager;

    /**
     * @var MoveSemanticChecker
     */
    private $move_semantic_checker;

    public function __construct(
        ArtifactsDeletionManager $artifacts_deletion_manager,
        Tracker_XML_Exporter_ArtifactXMLExporter $xml_exporter,
        MoveChangesetXMLUpdater $xml_updater,
        Tracker_Artifact_XMLImport $xml_import,
        Tracker_Artifact_PriorityManager $artifact_priority_manager,
        MoveSemanticChecker $move_semantic_checker
    ) {
        $this->artifacts_deletion_manager = $artifacts_deletion_manager;
        $this->xml_exporter               = $xml_exporter;
        $this->xml_updater                = $xml_updater;
        $this->xml_import                 = $xml_import;
        $this->artifact_priority_manager  = $artifact_priority_manager;
        $this->move_semantic_checker      = $move_semantic_checker;
    }

    /**
     * @throws \Tuleap\Tracker\Artifact\ArtifactsDeletion\ArtifactsDeletionLimitReachedException
     * @throws \Tuleap\Tracker\Artifact\ArtifactsDeletion\DeletionOfArtifactsIsNotAllowedException
     * @throws MoveArtifactNotDoneException
     * @throws MoveArtifactSemanticsException
     */
    public function move(Tracker_Artifact $artifact, Tracker $target_tracker, PFUser $user)
    {
        $this->artifact_priority_manager->startTransaction();

        $source_tracker = $artifact->getTracker();
        try {
            $this->move_semantic_checker->checkSemanticsAreAligned($source_tracker, $target_tracker);
        } catch (MoveArtifactSemanticsException $exception) {
            $this->artifact_priority_manager->rollback();
            throw $exception;
        }

        $xml_artifacts = $this->getXMLRootNode();
        $this->xml_exporter->exportFullHistory(
            $xml_artifacts,
            $artifact
        );

        $global_rank = $this->artifact_priority_manager->getGlobalRank($artifact->getId());
        $limit       = $this->artifacts_deletion_manager->deleteArtifactBeforeMoveOperation($artifact, $user);

        $this->xml_updater->update(
            $source_tracker,
            $target_tracker,
            $xml_artifacts->artifact,
            $artifact->getSubmittedByUser(),
            $artifact->getSubmittedOn()
        );

        if (! $this->processMove($xml_artifacts->artifact, $target_tracker, $global_rank)) {
            $this->artifact_priority_manager->rollback();
            throw new MoveArtifactNotDoneException();
        }

        $this->artifact_priority_manager->commit();
        return $limit;
    }

    private function processMove(SimpleXMLElement $artifact_xml, Tracker $tracker, $global_rank)
    {
        $tracker->getWorkflow()->disable();

        $moved_artifact = $this->xml_import->importArtifactWithAllDataFromXMLContent($tracker, $artifact_xml);

        if (! $moved_artifact) {
            return false;
        }

        $this->artifact_priority_manager->putArtifactAtAGivenRank($moved_artifact, $global_rank);
        return true;
    }

    private function getXMLRootNode()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><artifacts />';

        return new SimpleXMLElement($xml);
    }
}
