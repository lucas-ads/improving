<?php

use App\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cats = [
            'Administração',
            'Gestão',
            'Comunicação e Marketing',
            'Idiomas',
            'Direito',
            'Informática',
            'Saúde'
        ];

        foreach($cats as $category){
            $cat= new Category;
            $cat->title=$category;
            $cat->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $categories = Category::all();

        foreach($categories as $category){
            $category->delete();
        }
    }
}
