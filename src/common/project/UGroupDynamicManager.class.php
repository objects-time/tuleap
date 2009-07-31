<?php
/**
 * Copyright (c) STMicroelectronics, 2004-2009. All rights reserved
 *
 * This file is a part of Codendi.
 *
 * Codendi is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Codendi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Codendi. If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'UGroupDynamic.class.php';
require_once 'common/dao/UGroupDynamicManagerDao.class.php';

class UGroupDynamicManager {
    function getInstanceFromRow($row) {
        $r = array('ugroup_id'   => $row['ugroup_id'],
                   'name'        => $row['ugroup_name'],
                   'description' => $row['ugroup_description'],
                   'group_id'    => $row['ugroup_group_id']);
        return new UGroupStatic($row);
    }
    /**
     * Return on ugroup based on it's id
     * 
     * @param Integer $id          UGroup id
     * @param Boolean $withMembers Return group with member list prefiled
     * 
     * @return Ugroup
     */
    public function getGroupById($id, $groupId, $withMembers=false) {
        $uGroup = null;
        $dao    = $this->getDao();
        $dar    = $dao->searchById($id, $groupId, $withMembers);
        if ($dar && $dar->rowCount() > 0) {
            $uGroup = $this->getInstanceFromRow($dar->getRow());
            if ($withMembers) {
                $um = $this->getUserManager();
                foreach($dar as $row) {
                    $uGroup->addUser($um->getUserByRow($row));
                }
            }
        }
        return $uGroup;
    }

    /**
     * 
     * @return UGroupStaticManagerDao
     */
    public function getDao() {
        return new UGroupDynamicManagerDao(CodendiDataAccess::instance());
    }
    
    /**
     * 
     * @return UserManager
     */
    public function getUserManager() {
        return UserManager::instance();
    }
}

?>