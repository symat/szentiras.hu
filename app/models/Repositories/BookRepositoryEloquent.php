<?php
/**

 */

namespace SzentirasHu\Models\Repositories;


use SzentirasHu\Models\Entities\Book;

class BookRepositoryEloquent implements BookRepository {


    public function getBooksByTranslation($translationId)
    {
        return Book::where('translation_id', $translationId)->remember(120)->orderBy('id')->get();
    }
}