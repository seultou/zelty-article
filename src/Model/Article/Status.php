<?php

declare(strict_types=1);

namespace App\Model\Article;

enum Status: string {
    case Draft = 'DRAFT';
    case Published = 'PUBLISHED';
    case Deleted = 'DELETED';
}
