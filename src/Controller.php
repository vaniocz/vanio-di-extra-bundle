<?php
namespace Vanio\VanioDiExtraBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Vanio\VanioDiExtraBundle\DependencyInjection\ContainerAwareTrait;

abstract class Controller extends BaseController
{
    use ContainerAwareTrait;
}
