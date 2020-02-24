<?php
/**
 * Copyright (c) Enalean, 2020-Present. All Rights Reserved.
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

declare(strict_types=1);

namespace Tuleap\OAuth2Server\Grant;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tuleap\Request\DispatchablePSR15Compatible;
use Tuleap\Request\DispatchableWithRequestNoAuthz;

final class AuthCodeGrantController extends DispatchablePSR15Compatible implements DispatchableWithRequestNoAuthz
{
    /**
     * @var ResponseFactoryInterface
     */
    private $response_factory;
    /**
     * @var StreamFactoryInterface
     */
    private $stream_factory;
    /**
     * @var AuthorizationCodeGrantResponseBuilder
     */
    private $response_builder;
    /**
     * @var \UserManager
     */
    private $user_manager;

    public function __construct(
        ResponseFactoryInterface $response_factory,
        StreamFactoryInterface $stream_factory,
        AuthorizationCodeGrantResponseBuilder $response_builder,
        \UserManager $user_manager,
        EmitterInterface $emitter,
        MiddlewareInterface ...$middleware_stack
    ) {
        parent::__construct($emitter, ...$middleware_stack);
        $this->response_factory = $response_factory;
        $this->stream_factory   = $stream_factory;
        $this->response_builder = $response_builder;
        $this->user_manager     = $user_manager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $representation = $this->response_builder->buildResponse(
            new \DateTimeImmutable(),
            OAuth2AuthorizationCode::approveForDemoScope($this->user_manager->getUserByUserName('admin'))
        );

        return $this->response_factory->createResponse()
            ->withHeader('Content-Type', 'application/json;charset=UTF-8')
            ->withBody(
                $this->stream_factory->createStream(
                    json_encode($representation, JSON_THROW_ON_ERROR)
                )
            );
    }
}