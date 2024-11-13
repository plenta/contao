<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Filesystem;

use Contao\CoreBundle\File\Metadata;
use Contao\CoreBundle\Filesystem\ExtraMetadata;
use Contao\CoreBundle\Tests\TestCase;
use Contao\Image\ImportantPart;

class ExtraMetadataTest extends TestCase
{
    public function testSetAndGetValues(): void
    {
        $data = [
            'foo' => 42,
            'localized' => [
                'en' => new Metadata(['bar' => 'baz']),
            ],
            'importantPart' => new ImportantPart(0.5, 0, .5, 0),
        ];

        $extraMetadata = new ExtraMetadata($data);

        $this->assertSame(42, $extraMetadata->get('foo'));
        $this->assertSame(42, $extraMetadata['foo']);
        $this->assertTrue(isset($extraMetadata['foo']));

        $this->assertSame('baz', $extraMetadata->get('localized')['en']->get('bar'));
        $this->assertSame('baz', $extraMetadata->getLocalized()['en']->get('bar'));

        $this->assertSame(0.5, $extraMetadata->get('importantPart')->getX());
        $this->assertSame(0.5, $extraMetadata->getImportantPart()->getX());

        $this->assertNull($extraMetadata->get('non-existent'));
        $this->assertFalse(isset($extraMetadata['non-existent']));

        $this->assertSame($data, $extraMetadata->all());
    }
}
