<?php
namespace Rebase\BigvBundle\AltObj\CollectionPersist;



class Collectionator {
  
  private $OldObjects;
  private $NewObjects;
  private $Children;
  private $sets;
  private $col;
  private $em;
  
  public function __construct($em, $collection, $children, $sets)
  {
    $this->em = $em;
    $this->col = $collection;
    $this->OldObjects = array();
    $this->NewObjects = array();
    $this->Children = $children;
    $this->sets = $sets;

    foreach ($collection as $item)
    {
      $this->OldObjects[] = $item;
    }
  }

  public function persist()
  {
    foreach ($this->col as $item)
    {
      $this->NewObjects[] = $item;
    }    
    foreach ($this->NewObjects as $n)
    {
      foreach ($this->OldObjects as $key => $o)
      {
        if ($o->getId() === $n->getId())
        {
          unset($this->OldObjects[$key]);
        }
      }
    }
    foreach ($this->OldObjects as $delme)
    {
      foreach ($this->Children as $c)
      {
        $fn = "get{$c}";
        $n = $delme->$fn();//call_user_func("\$c->get$c");
        if ($n != null)
        {
          $this->em->Remove($n);
        }
      }
      $this->em->Remove($delme);
    }
    foreach ($this->NewObjects as $new)
    {
      foreach ($this->sets as $meth => $val)
      {
        $fn = "set$meth";
        $new->$fn($val);
      }
      foreach ($this->Children as $c)
      {
        $fn = "get{$c}";
        $n = $new->$fn();//call_user_func("\$c->get$c");
        if ($n != null)
        {
          $this->em->Persist($n);
        }
      }
      
      $this->em->Persist($new);
    }
    
    return true;
  } 
}

?>
