<?php
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

namespace Tuleap\REST;

/** I am useful to extract stuff in the 'query' parameter of a REST route */
class QueryParameterParser
{
    private $json_decoder;

    public function __construct(JsonDecoder $json_decoder)
    {
        $this->json_decoder = $json_decoder;
    }

    /**
     * @param string $query
     * @param string $parameter_name
     * @return int[]
     * @throws QueryParameterException
     */
    public function getArrayOfInt($query, $parameter_name)
    {
        $parameter_content = $this->getParameterContent($query, $parameter_name);
        if (! is_array($parameter_content)) {
            throw new InvalidParameterTypeException($parameter_name);
        }

        $only_numeric_label_ids = array_filter($parameter_content, 'is_int');
        if ($only_numeric_label_ids !== $parameter_content) {
            throw new InvalidParameterTypeException($parameter_name);
        }

        $duplicates = array_diff_key($parameter_content, array_unique($parameter_content));
        if (count($duplicates) > 0) {
            throw new DuplicatedParameterValueException($parameter_name, $duplicates);
        }

        return $parameter_content;
    }

    /**
     * @param string $query
     * @param string $parameter_name
     * @return int
     * @throws QueryParameterException
     */
    public function getInt($query, $parameter_name)
    {
        $parameter_content = $this->getParameterContent($query, $parameter_name);
        if (! is_int($parameter_content)) {
            throw new InvalidParameterTypeException($parameter_name);
        }

        return $parameter_content;
    }

    private function getParameterContent($query, $parameter_name)
    {
        $query = trim($query);
        $json_query = $this->json_decoder->decodeAsAnArray('query', $query);

        if (! isset($json_query[$parameter_name])) {
            throw new MissingMandatoryParameterException($parameter_name);
        }

        return $json_query[$parameter_name];
    }
}
