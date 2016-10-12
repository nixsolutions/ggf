<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\SessionManager;
use Illuminate\Http\Request;
use Closure;

/**
 * Class StartSession
 * @package App\Http\Middleware
 */
class StartSession extends \Illuminate\Session\Middleware\StartSession
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new session middleware.
     *
     * @param  \Illuminate\Session\SessionManager $manager
     * @return void
     */
    public function __construct(SessionManager $manager, Guard $auth)
    {
        parent::__construct($manager);
        $this->manager = $manager;
        $this->auth = $auth;
    }

    /**
     * @inheritdoc
     */
    public function getSession(Request $request)
    {
        $session = $this->manager->driver();
        $session->setId($this->getSessionId($request, $session));

        return $session;
    }

    /**
     * @param Request $request
     * @param $session
     * @return mixed
     */
    protected function getSessionId(Request $request, $session)
    {
        return $this->auth->getSession($request);
    }

    /**
     * Handle an incoming request, but skip OPTIONS method
     *
     * @inheritdoc
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('options')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
