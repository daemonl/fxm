<?php

namespace Rebase\BigvBundle\Statics;

class bigv_extension extends \Twig_Extension
{
  public function getGlobals()
  {
    return array(
      'context'=> new twig_context(),
    );
  }
    
  public function getName()
  {
    return 'rebase_extension';
  }
    
}

?>
