<?php

namespace App\Tests\Form\Type;

use App\Entity\Email;
use App\Form\EmailType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class EmailTypeTests extends TypeTestCase
{
    public function testSubmit()
    {
        $formData = [
            "name" => "David Jungman",
            "clientEmail" => "davidjungman.web@gmail.com",
            "subject" => "Test",
            "message" => "This is just testing message"
        ];

        $objectToCompare = new Email();
        $form = $this->factory->create(EmailType::class, $objectToCompare);

        $object = new Email();
        $object
            ->setName($formData["name"])
            ->setClientEmail($formData["clientEmail"])
            ->setSubject($formData["subject"])
            ->setMessage($formData["message"]);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        // modified as expected
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach(array_keys($formData)as $key)
        {
            $this->assertArrayHasKey($key, $children);
        }
    }
}