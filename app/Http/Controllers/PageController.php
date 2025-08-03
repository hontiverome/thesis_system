<?php
/*
* System Name: Theming and UI Framework
* Module Name: Page Controller
* Purpose Of this file: 
* To handle routes and serve pages
* 
* Author: Jerome Andrei O. Hontiveros
* Copyright (C) 2025
* by the Department of Science and Technology — Project LODI
* All rights reserved.
* 
* Permission is hereby granted, free of charge, to any persons obtaining a copy
* of this software and associated documentation files, to deal in the Software
* without restriction, including the rights to use, copy, modify, merge,
* publish, distribute, sublicense, and/or sell copies of the Software, and to
* permit persons to whom the Software is furnished to do so, provided that the
* above copyright notice(s) and this permission notice appears in all copies of
* the Software and that both the above copyright notice(s) and this permission
* notice appear in supporting documentation.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS.
* IN NO EVENT SHALL THE COPYRIGHT HOLDER OR HOLDERS INCLUDED IN THIS NOTICE BE
* LIABLE FOR ANY CLAIM, OR ANY SPECIAL INDIRECT OR CONSEQUENTIAL DAMAGES, OR ANY
* DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
* ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
* CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
* 
* Except as contained in this notice, the name of a copyright holder shall not
* be used in advertising or otherwise to promote the sale, use or other dealings
* in this Software without prior written authorization of the copyright holder.
*/
namespace App\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Show the home page.
     */
    public function home()
    {
        return Inertia::render('home_view');
    }

    /**
     * Show the dashboard page.
     */
    public function dashboard()
    {
        return Inertia::render('dashboard_view');
    }

    /**
     * Show the settings page.
     */
    public function settings()
    {
        return Inertia::render('settings_view');
    }

    /**
     * Show the profile page.
     */
    public function profile()
    {
        return Inertia::render('profile_view');
    }
    
    /**
     * Show the help page.
     */
    public function help()
    {
        return Inertia::render('help_view');
    }
}
