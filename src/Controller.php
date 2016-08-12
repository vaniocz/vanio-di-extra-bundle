<?php
namespace Vanio\DiExtraBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Vanio\DiExtraBundle\DependencyInjection\ContainerAwareTrait;

abstract class Controller extends BaseController
{
    use ContainerAwareTrait;
}
