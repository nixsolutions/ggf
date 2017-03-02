<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Tests\TestCase;
use App\Member;
use Illuminate\Support\Facades\Auth;

class MemberApiTest extends TestCase
{
    use DatabaseTransactions;

    private $structure = ['id', 'name', 'facebookId', 'created_at', 'updated_at'];

    public function testCurrentMember()
    {
        $member = factory(Member::class)->create();
        Auth::login($member);

        $this->get('/api/v1/me')
            ->assertJsonStructure($this->structure);
    }
}
