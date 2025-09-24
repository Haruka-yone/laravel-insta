<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // To display the categories (Admin side)
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }
}
