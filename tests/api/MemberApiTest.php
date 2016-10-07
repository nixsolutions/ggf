<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;
use App\Member;
use Illuminate\Support\Facades\Auth;

class MemberApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCurrentMember()
    {
        $member = factory(Member::class)->create();

        Auth::login($member);

        $this->get('/api/v1/me')
            ->seeJsonStructure([
                'id',
                'name',
                'facebookId',
                'created_at',
                'updated_at'
            ]);
    }
}
