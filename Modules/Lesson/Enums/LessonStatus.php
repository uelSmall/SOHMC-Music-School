<?php

namespace Modules\Lesson\Enums;

enum LessonStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
