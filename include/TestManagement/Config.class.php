<?php
/**
 * Copyright (c) Enalean, 2014-Present. All Rights Reserved.
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

namespace Tuleap\TestManagement;

use Project;

class Config
{

    /** @var Dao */
    private $dao;

    /**
     * @var array
     */
    private $properties = array();

    public function __construct(Dao $dao)
    {
        $this->dao = $dao;
    }

    public function setProjectConfiguration(
        Project $project,
        $campaign_tracker_id,
        $test_definition_tracker_id,
        $test_execution_tracker_id,
        $issue_tracker_id
    ) {
        unset($this->properties[$project->getID()]);

        return $this->dao->saveProjectConfig(
            $project->getId(),
            $campaign_tracker_id,
            $test_definition_tracker_id,
            $test_execution_tracker_id,
            $issue_tracker_id
        );
    }

    /**
     * @return int|false
     */
    public function getCampaignTrackerId(Project $project)
    {
        return $this->getTrackerID($project, 'campaign_tracker_id');
    }

    /**
     * @return int|false
     */
    public function getTestExecutionTrackerId(Project $project)
    {
        return $this->getTrackerID($project, 'test_execution_tracker_id');
    }

    /**
     * @return int|false
     */
    public function getTestDefinitionTrackerId(Project $project)
    {
        return $this->getTrackerID($project, 'test_definition_tracker_id');
    }

    /**
     * @return int|false
     */
    public function getIssueTrackerId(Project $project)
    {
        return $this->getTrackerID($project, 'issue_tracker_id');
    }

    /**
     * @return int|false
     */
    private function getTrackerID(Project $project, string $key)
    {
        $id = $this->getProperty($project, $key);
        if ($id === false) {
            return $id;
        }

        return (int) $id;
    }

    public function isConfigNeeded(Project $project)
    {
        return (! $this->getCampaignTrackerId($project)) ||
            (! $this->getTestDefinitionTrackerId($project)) ||
            (! $this->getTestExecutionTrackerId($project)) ||
            (! $this->getIssueTrackerId($project));
    }

    private function getProperty(Project $project, $key)
    {
        $project_id = $project->getID();

        if (! isset($this->properties[$project_id])) {
            $this->properties[$project_id] = $this->getPropertiesForProject($project);
        }

        if (! isset($this->properties[$project_id][$key])) {
            return false;
        }

        return $this->properties[$project_id][$key];
    }

    private function getPropertiesForProject(Project $project)
    {
        return $this->dao->searchByProjectId($project->getId())->getRow();
    }
}
