<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Tasks;

Route::get('/', Tasks::class)->name('home');
