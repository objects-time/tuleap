<?php
/**
 * Copyright (c) Xerox Corporation, Codendi Team, 2001-2009. All rights reserved
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

require_once 'common/dao/include/DataAccessObject.class.php';

/**
 *  Data Access Object for UGroup 
 */
class UGroupDynamicManagerDao extends DataAccessObject {

    /**
     * Searches static UGroup by GroupId
     * return all static ugroups
     * @return DataAccessResult
     */
    function searchById($id, $groupId, $withMembers=false) {
        $id      = $this->da->escapeInt($id);
        $groupId = $this->da->escapeInt($groupId);
        $select  = '';
        $from    = '';
        $orderBy = '';
        if ($withMembers) {
            $groupJoin = '';
            
            $select = ', J.*';
            $from   = ' JOIN ('.
                      '  SELECT u.* '.
                      '  FROM user_group ug '.
                      '    JOIN user u ON (u.user_id = ug.user_id)'.
                      $groupJoin.
                      '  WHERE ug.group_id = '.$groupId.
                      ') AS J';
        }
        $sql = 'SELECT ug.ugroup_id as ugroup_id, ug.name as ugroup_name, ug.description as ugroup_description, '.$groupId.' as ugroup_group_id'.
               $select.
               ' FROM ugroup ug'.
               $from. 
               ' WHERE ug.ugroup_id = '.$this->da->escapeInt($id).
               $orderBy;
        return $this->retrieve($sql);
    }

}
?>