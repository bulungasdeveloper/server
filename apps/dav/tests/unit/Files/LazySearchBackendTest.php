<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\DAV\Tests\unit\Files;

use OCA\DAV\Files\LazySearchBackend;
use SearchDAV\Backend\ISearchBackend;
use SearchDAV\Query\Query;
use Test\TestCase;

class LazySearchBackendTest extends TestCase {
	public function testGetFileIdsDelegatesWhenBackendHasMethod(): void {
		$lazy = new LazySearchBackend();
		$lazy->setBackend(new LazySearchBackendWithFileIdsStub([42, 7]));

		$this->assertSame([42, 7], $lazy->getFileIds());
	}

	public function testGetFileIdsReturnsEmptyWhenBackendLacksMethod(): void {
		$lazy = new LazySearchBackend();
		$lazy->setBackend($this->createMock(ISearchBackend::class));

		$this->assertSame([], $lazy->getFileIds());
	}
}

/**
 * ISearchBackend has no getFileIds(); this stub models a backend that
 * provides it so the defensive delegation in LazySearchBackend can be covered.
 */
class LazySearchBackendWithFileIdsStub implements ISearchBackend {
	/** @var int[] */
	private array $ids;

	/** @param int[] $ids */
	public function __construct(array $ids) {
		$this->ids = $ids;
	}

	/** @return int[] */
	public function getFileIds(): array {
		return $this->ids;
	}

	public function getArbiterPath(): string {
		return '';
	}

	public function isValidScope(string $href, $depth, ?string $path): bool {
		return false;
	}

	public function getPropertyDefinitionsForScope(string $href, ?string $path): array {
		return [];
	}

	public function search(Query $query): array {
		return [];
	}

	public function preloadPropertyFor(array $nodes, array $requestProperties): void {
	}
}
