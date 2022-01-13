<?php

namespace App\Tests\Form\Type;

use App\Entity\Blog;
use App\Form\Type\BlogFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class BlogFormTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = [
            'test' => 'test',
            'test2' => 'test2',
        ];

        $model = new Blog();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(BlogFormType::class, $model);

        $expected = new Blog();
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }

}