<?php

namespace SzentirasHu\Models\Repositories;


use SzentirasHu\Models\Entities\Book;
use SzentirasHu\Models\Entities\Translation;

interface TranslationRepository {

    /**
     * @return Translation[]
     */
    public function getAll();

    /**
     * @return Translation[]
     */
    public function getAllOrderedByDenom();

    /**
     * @param bool $denom
     * @return Translation[]
     */
    public function getByDenom($denom = false);

    /**
     * @param Translation $translation
     * @return Book[]
     */
    public function getBooks($translation);

    /**
     * @param $abbrev
     * @return Translation
     */
    public function getByAbbrev($abbrev);

    /**
     * @param $id
     * @return Translation
     */
    public function getById($id);

    /**
     * @return Translation
     */
    public function getDefault();


} 