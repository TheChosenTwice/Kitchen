<?php

namespace App\Controllers;

use App\Models\Category;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class CategoryController extends BaseController
{
    public function index(Request $request): Response
    {
        // TODO: Implement index() method.
        return $this->html();
    }
}