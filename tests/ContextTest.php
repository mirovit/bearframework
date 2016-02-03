<?php

/*
 * Bear Framework
 * http://bearframework.com
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

/**
 * 
 */
class ContextTest extends PHPUnit_Framework_TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testAppContext()
    {

        $appDir = sys_get_temp_dir() . '/app' . uniqid() . '/';
        App\Utilities\Dir::make($appDir);

        $app = new App([
            'appDir' => $appDir
        ]);
        $app->request->base = 'http://example.com/www';

        file_put_contents($appDir . 'index.php', '<?php ');
        file_put_contents($appDir . 'class1.php', '<?php class TempClass1{}');
        file_put_contents($appDir . 'class2.php', '<?php class TempClass2{}');

        $context = new App\AppContext($appDir);

        $this->assertTrue($context->load('class1.php'));
        $this->assertTrue(class_exists('TempClass1'));

        $context->classes->add('TempClass2', 'class2.php');
        $this->assertTrue(class_exists('TempClass2'));

        $this->assertTrue(strpos($context->assets->getUrl('assets/logo.png'), $app->request->base) === 0);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAddonContext()
    {

        $addonsDir = sys_get_temp_dir() . '/addons/';
        $addonID = 'tempaddong' . uniqid();
        $addonDir = $addonsDir . $addonID . '/';
        App\Utilities\Dir::make($addonDir);

        $app = new App([
            'addonsDir' => $addonsDir
        ]);
        $app->request->base = 'http://example.com/www';

        file_put_contents($addonDir . 'index.php', '<?php ');
        file_put_contents($addonDir . 'class1.php', '<?php class TempClass1{}');
        file_put_contents($addonDir . 'class2.php', '<?php class TempClass2{}');
        $app->addons->add($addonID, ['option1' => 5]);

        $context = new App\AddonContext($addonDir);

        $options = $context->getOptions();
        $this->assertTrue(isset($options['option1']));
        $this->assertTrue($options['option1'] === 5);

        $this->assertTrue($context->load('class1.php'));
        $this->assertTrue(class_exists('TempClass1'));

        $context->classes->add('TempClass2', 'class2.php');
        $this->assertTrue(class_exists('TempClass2'));

        $this->assertTrue(strpos($context->assets->getUrl('assets/logo.png'), $app->request->base) === 0);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAppContextInvalidArguments1()
    {
        $this->setExpectedException('InvalidArgumentException');
        new App\AppContext(1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAddonContextInvalidArguments1()
    {
        $this->setExpectedException('InvalidArgumentException');
        new App\AddonContext(1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAddonContextInvalidArguments2()
    {
        $app = new App(); // missing addons dir
        $this->setExpectedException('Exception');
        $context = new App\AddonContext('dir');
        $context->getOptions();
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextInvalidArguments1()
    {
        $this->setExpectedException('InvalidArgumentException');
        new App\Context(1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextInvalidArguments2()
    {
        $this->setExpectedException('InvalidArgumentException');
        $context = new App\Context('dir');
        $context->load(1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextAssetsInvalidArguments1()
    {
        $this->setExpectedException('InvalidArgumentException');
        new App\Context\Assets(1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextAssetsInvalidArguments2()
    {
        $this->setExpectedException('InvalidArgumentException');
        $contextAssets = new App\Context\Assets('dir');
        $contextAssets->getUrl(1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextAssetsInvalidArguments3()
    {
        $this->setExpectedException('InvalidArgumentException');
        $contextAssets = new App\Context\Assets('dir');
        $contextAssets->getUrl('file.png', 1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextClassesInvalidArguments1()
    {
        $this->setExpectedException('InvalidArgumentException');
        new App\Context\Classes(1);
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextClassesInvalidArguments2()
    {
        $this->setExpectedException('InvalidArgumentException');
        $contextClasses = new App\Context\Classes('dir');
        $contextClasses->add(1, 'testclass.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testContextClassesInvalidArguments3()
    {
        $this->setExpectedException('InvalidArgumentException');
        $contextClasses = new App\Context\Classes('dir');
        $contextClasses->add('TestClass', 1);
    }

}
