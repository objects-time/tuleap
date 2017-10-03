/**
 * Copyright (c) Enalean, 2017. All Rights Reserved.
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

import { recursiveGet } from 'tlp';

export {
    getLabeledItems
}

async function getLabeledItems(project_id, labels_id) {
    const labeled_items = await recursiveGet(`/api/projects/${project_id}/labeled_items`, {
        params: {
            query: { labels_id },
            limit: 50
        },
        getCollectionCallback: (json) => [].concat(json.labeled_items)
    });

    return labeled_items;
}
