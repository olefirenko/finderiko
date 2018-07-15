<?php

namespace App\Models;

use Baum\Node;

/**
* Node
*/
class AmazonNode extends Node {

  protected $table = 'nodes';

  protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');

}
