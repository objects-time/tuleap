<?php
/**
 * Copyright (c) Enalean, 2012. All Rights Reserved.
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

class Planning_ShortAccessMilestonePresenter extends Planning_MilestoneLinkPresenter {

    /** @var bool */
    private $is_latest = false;

    /** @var User */
    private $user;

    /** @var Planning_ShortAccess */
    private $short_access;

    /** @var array of AgileDashboard_Pane */
    private $additional_panes;

    /** @var string */
    private $theme_path;

    public function __construct(Planning_ShortAccess $short_access, Planning_Milestone $milestone, User $user, $theme_path) {
        parent::__construct($milestone);
        $this->short_access = $short_access;
        $this->user         = $user;
        $this->theme_path   = $theme_path;
    }

    public function additionalPanes() {
        if (!$this->additional_panes) {
            $active_pane = null;
            $this->additional_panes = array();
            EventManager::instance()->processEvent(
                AGILEDASHBOARD_EVENT_ADDITIONAL_PANES_ON_MILESTONE,
                array(
                    'milestone'   => $this->milestone,
                    'user'        => $this->user,
                    'request'     => HTTPRequest::instance(),
                    'panes'       => &$this->additional_panes,
                    'active_pane' => $active_pane,
                )
            );
        }
        $milestone_planning_pane_info = new AgileDashboard_MilestonePlanningPaneInfo($this->theme_path);
        $panes = array(
            $milestone_planning_pane_info->getIconTemplateParametersForMilestone($this->milestone)
        );
        foreach ($this->additional_panes as $pane_info) {
            /* @var $pane_info AgileDashboard_PaneInfo */
            $panes[] = $pane_info->getIconTemplateParametersForMilestone($this->milestone);
        }
        return $panes;
    }

    public function getContent() {
        $pane = null;
        EventManager::instance()->processEvent(
            AGILEDASHBOARD_EVENT_INDEX_PAGE,
            array(
                'milestone'   => $this->milestone,
                'user'        => $this->user,
                'pane'        => &$pane,
            )
        );
        if ($pane) {
            return $pane->getMinimalContent();
        }
        return '';
    }

    public function getBacklogTrackerId() {
        return $this->milestone->getTrackerId();
    }

    public function setIsLatest() {
        $this->is_latest = true;
    }

    public function isLatest() {
        return $this->is_latest;
    }

    public function is_active() {
        return $this->isLatest() && $this->short_access->isLatest() && count($this->additionalPanes());
    }
}
?>
