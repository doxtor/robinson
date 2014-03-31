<?php
namespace Robinson\Frontend\Tests\Controllers;

class CategoryControllerTest extends BaseTestController
{
    public function setUp(\Phalcon\DiInterface $di = null, \Phalcon\Config $config = null)
    {
        parent::setUp($di, $config);
        $this->populateTable('categories');
        $this->populateTable('packages');
        $this->populateTable('package_tabs');
        $this->populateTable('package_tags');
    }

    public function testIndexActionShouldWorkAsExpected()
    {
        $imagineMock = $this->getMockBuilder('Imagine\Imagick\ImagineInterface')
            ->setMethods(array('open'))
            ->getMock();
        $imageMock = $this->getMockBuilder('Imagine\Imagick\ImageInterface')
            ->setMethods(array('thumbnail', 'save', 'getSize', 'paste', 'resize'))
            ->getMock();
        $imageMock->expects($this->any())
            ->method('thumbnail')
            ->will($this->returnSelf());
        $imageMock->expects($this->any())
            ->method('resize')
            ->will($this->returnValue(true));
        $imageMock->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));
        $imageMock->expects($this->any())
            ->method('paste')
            ->will($this->returnSelf());
        $imageMock->expects($this->any())
            ->method('getSize')
            ->will($this->returnValue(new \Imagine\Image\Box(600, 300)));

        $imagineMock->expects($this->any())
            ->method('open')
            ->will($this->returnValue($imageMock));
        $this->getDI()->set('imagine', $imagineMock);

        $this->dispatch('/fixture-category/1');

        $this->assertController('category');
        $this->assertAction('index');
        $this->assertResponseContentContains('description test fixture category');
    }
} 