<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Filesystem;

use Contao\Image\ImportantPart;

/**
 * @experimental
 *
 *  This class acts as a collection for arbitrary Virtual Filesystem (VFS)
 *  metadata, that you get when calling getExtraMetadata() on a
 *  \Contao\CoreBundle\Filesystem\VirtualFilesystemInterface.
 *
 * @implements \ArrayAccess<string, mixed>
 */
class ExtraMetadata implements \ArrayAccess
{
    /**
     * @var array<string, mixed>
     */
    private array $extraMetadata;

    /**
     * @param array<string, mixed> $extraMetadata
     */
    public function __construct(array $extraMetadata = [])
    {
        $this->extraMetadata = $extraMetadata;

        $localizedMetadata = $extraMetadata['metadata'] ?? null;

        if (\is_array($localizedMetadata)) {
            $this->extraMetadata['localized'] = $localizedMetadata;
            unset($this->extraMetadata['metadata']);
        }
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        if ('metadata' === $key) {
            $key = 'localized';
        }

        return $this->extraMetadata[$key] ?? null;
    }

    /**
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        if ('metadata' === $key) {
            $key = 'localized';
        }

        $this->extraMetadata[$key] = $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->extraMetadata;
    }

    /**
     * Returns an array of localized metadata.
     * WARNING: This will return a class MetadataBag of localized metadata in Contao 5.
     *
     * This might only be available if the metadata was generated by the core's
     * default DBAFS implementation and the respective data is available.
     */
    public function getLocalized(): array
    {
        return $this->get('localized');
    }

    /**
     * Returns an ImportantPart of an image.
     *
     * This might only be available if the metadata was generated by the core's
     * default DBAFS implementation and the respective data is available.
     */
    public function getImportantPart(): ?ImportantPart
    {
        return $this->get('importantPart');
    }

    public function offsetExists($offset): bool
    {
        return \is_string($offset) && null !== $this->get($offset);
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if ((\is_string($offset) && $value = $this->get($offset))) {
            return $value;
        }

        throw new \OutOfBoundsException(sprintf('The key "%s" does not exist in this extra metadata bag.', $offset));
    }

    public function offsetSet($offset, $value): void
    {
        throw new \RuntimeException('Setting metadata is not supported in this extra metadata bag.');
    }

    public function offsetUnset($offset): void
    {
        throw new \RuntimeException('Unsetting metadata is not supported in this extra metadata bag.');
    }
}
