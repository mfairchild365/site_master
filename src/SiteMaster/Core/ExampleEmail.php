<?php
namespace SiteMaster\Core;

class ExampleEmail extends EmailInterface
{
    public function getSubject()
    {
        return 'Example Email';
    }

    public function getTo()
    {
        return false;
    }
}
