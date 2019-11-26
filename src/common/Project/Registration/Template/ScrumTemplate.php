<?php
/**
 * Copyright (c) Enalean, 2019-Present. All Rights Reserved.
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
 *
 */

declare(strict_types=1);

namespace Tuleap\Project\Registration\Template;

use Tuleap\Glyph\Glyph;
use Tuleap\Glyph\GlyphFinder;
use Tuleap\XML\ProjectXMLMerger;

class ScrumTemplate implements ProjectTemplate
{
    public const NAME = 'scrum';

    private const PROJECT_XML = __DIR__ . '/../../../../../tools/utils/setup_templates/scrum/project.xml';
    private const AGILEDASHBOARD_XML = __DIR__.'/../../../../../plugins/agiledashboard/www/resources/scrum_dashboard_template.xml';

    /**
     * @var string
     */
    private $title;
    /**s
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $xml_path;
    /**
     * @var GlyphFinder
     */
    private $glyph_finder;
    /**
     * @var ProjectXMLMerger
     */
    private $project_xml_merger;

    public function __construct(GlyphFinder $glyph_finder, ProjectXMLMerger $project_xml_merger)
    {
        $this->title              = _('Scrum');
        $this->description        = _('Manage your project using epics and user stories in releases and sprints');
        $this->glyph_finder       = $glyph_finder;
        $this->project_xml_merger = $project_xml_merger;
    }

    /**
     * Actual XML file is generated "on the fly" in order to guaranty the consistency between the AgileDashboard
     * "Start Scrum" template and the "Project Creation" Scrum template
     */
    public function getXMLPath(): string
    {
        $base_dir = \ForgeConfig::getCacheDir() . '/scrum_template';
        if (! is_dir($base_dir) && ! mkdir($base_dir, 0755) && ! is_dir($base_dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $base_dir));
        }
        $xml_path = \ForgeConfig::getCacheDir() . '/scrum_template/project.xml';
        $this->project_xml_merger->merge(
            self::PROJECT_XML,
            self::AGILEDASHBOARD_XML,
            $xml_path,
        );
        return $xml_path;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getGlyph(): Glyph
    {
        return $this->glyph_finder->get(self::NAME);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}