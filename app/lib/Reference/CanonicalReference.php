<?php

namespace SzentirasHu\Lib\Reference;

use SzentirasHu\Models\Entities\BookAbbrev;

/**
 * Class CanonicalReference to represent a unique reference to some Bible verses.
 * This reference is agnostic of translations, uses the primary
 *
 */
class CanonicalReference
{

    /**
     * @var BookRef[]
     */
    public $bookRefs;

    public function __construct($bookRefs = [])
    {
        $this->bookRefs = $bookRefs;
    }

    public static function isExistingBookRef($referenceString)
    {
        $ref = self::fromString($referenceString);
        $translationId = \Config::get("settings.defaultTranslationId");
        return self::findStoredBookRef($ref->bookRefs[0], $translationId);
    }

    public static function fromString($s)
    {
        $ref = new CanonicalReference();
        $parser = new ReferenceParser($s);
        $bookRefs = $parser->bookRefs();
        $ref->bookRefs = $bookRefs;
        return $ref;
    }

    public static function isValid($referenceString)
    {
        $ref = self::fromString($referenceString);
        return count($ref->bookRefs) > 0;
    }

    public function toString()
    {
        $s = '';
        $lastBook = end($this->bookRefs);
        foreach ($this->bookRefs as $bookRef) {
            $s .= $bookRef->toString();
            if ($lastBook !== $bookRef) {
                $s .= "; ";
            }
        }
        return $s;
    }

    public function toTranslated($translationId)
    {
        $bookRefs = array_map(function ($bookRef) use ($translationId) {
            return self::translateBookRef($bookRef, $translationId);
        }, $this->bookRefs);
        return new CanonicalReference($bookRefs);
    }

    /**
     *
     * Takes a bookref and get an other bookref according
     * to the given translation.
     *
     * @return BookRef
     */
    public static function translateBookRef(BookRef $bookRef, $translationId)
    {
        $result = self::findStoredBookRef($bookRef, $translationId);
        return $result ? $result : $bookRef;
    }

    private static function findStoredBookRef($bookRef, $translationId)
    {
        $result = false;
        $abbrev = BookAbbrev::where('abbrev', $bookRef->bookId)->first();
        if (!$abbrev) {
            \Log::debug("Book abbrev not found in database: {$abbrev}");
        } else {
            $book = $abbrev->books()->where('translation_id', $translationId)->first();
            if ($book) {
                $result = new BookRef($book->abbrev);
                $result->chapterRanges = $bookRef->chapterRanges;
            } else {
                \Log::debug("Book not found in database: abbrev: {$abbrev->abbrev}, book id: {$abbrev->books_id}");
            }
        }
        return $result;
    }

    public function isBookLevel()
    {
        foreach ($this->bookRefs as $bookRef) {
            if (count($bookRef->chapterRanges) > 0) {
                return false;
            }
        }
        return true;
    }

}
