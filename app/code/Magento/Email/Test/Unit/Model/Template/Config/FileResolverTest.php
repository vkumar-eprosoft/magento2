<?php declare(strict_types=1);
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Email\Test\Unit\Model\Template\Config;

use Magento\Email\Model\Template\Config\FileResolver;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\DirSearch;
use Magento\Framework\Config\FileIteratorFactory;
use PHPUnit\Framework\TestCase;

class FileResolverTest extends TestCase
{
    public function testGet()
    {
        $fileIteratorFactory = $this->createMock(FileIteratorFactory::class);
        $dirSearch = $this->createMock(DirSearch::class);
        $model = new FileResolver($fileIteratorFactory, $dirSearch);
        $expected = ['found_file'];
        $fileIteratorFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($expected));
        $dirSearch->expects($this->once())
            ->method('collectFiles')
            ->with(ComponentRegistrar::MODULE, 'etc/file');
        $model->get('file', 'scope');
    }
}
