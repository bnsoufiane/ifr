<?php


class AuthController extends Admin\BaseController
{
	protected $layout = 'layouts.sign-in';

    public function showAuthForm()
    {
		$this->layout->content = View::make('auth-form')
            ->with('error', Session::get('error'))
            ->with('success', Session::get('success'));
	}

    public function postAuthForm()
    {
		$username = Input::get('username');
		$password = Input::get('password');
        $remember = Input::has('remember');

		$credentials = array(
			'login' => $username,
			'password' => $password
		);

        try {
            Sentry::authenticate($credentials, $remember);
			return Redirect::route('index');
        } catch (\Exception $e) {
            return Redirect::route('sign-in')
                ->withInput()
                ->with('error', 'Wrong username or password.');
        }
	}

    public function signOut()
    {
        Sentry::logout();
        return Redirect::route('sign-in');
    }

    public function checkSession()
    {

        $bag = Session::getMetadataBag();
        $max = Config::get('session.lifetime') * 60;

        if ($bag && $max < (time() - $bag->getLastUsed())) {
            Sentry::logout();
            return "inactive";
        } else {
            return "active";
        }
    }


    public function forgot_password()
    {
        $this->layout->content = View::make('forgot_passsword/email-form')
            ->with('error', Session::get('error'));
    }

    public function postForgotPassword()
    {
        $email = Input::get('email');
        $user = \User::findByEmail($email);


        if ($user) {
            $resetCode = $user->getResetPasswordCode();

            Mail::send('emails.auth.reminder', array('id' => $user->id, 'token' => $resetCode), function ($message) use (&$user) {
                $message
                    ->to($user->email)
                    ->from('info@its-for-real.com', 'Its For Real')
                    ->subject('Reset Password');

            });

            return Redirect::route('password_email_sent');
        } else {
            return Redirect::route('forgot_password')
                ->withInput()
                ->with('error', 'Email not found.');
        }
    }

    public function password_email_sent()
    {
        $this->layout->content = View::make('forgot_passsword/password-sent')
            ->with('error', Session::get('error'));
    }

    public function reset_password($id, $resetCode)
    {
        try {
            $user = Sentry::findUserById($id);

            if ($user->checkResetPasswordCode($resetCode)) {
                $this->layout->content = View::make('forgot_passsword/reset-password-form')
                    ->with('user_id', $id)
                    ->with('reset_code', $resetCode)
                    ->with('error', Session::get('error'));
            } else {
                return Redirect::route('forgot_password')
                    ->withInput()
                    ->with('error', 'An error has occured. Please try again');
            }
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Redirect::route('forgot_password')
                ->withInput()
                ->with('error', 'An error has occured. Please try again');
        }

    }

    public function save_reset_password()
    {
        $input = \Input::all();

        $user_id = $input['user_id'];
        $resetCode = $input['reset_code'];


        try {
            $user = Sentry::findUserById($user_id);

            if ($user->checkResetPasswordCode($resetCode)) {

                $rules = array(
                    'new_password' => 'required|confirmed|min:5|max:50',
                    'new_password_confirmation' => 'required|min:5|max:50',
                );

                $validator = \Validator::make($input, $rules);

                if ($validator->fails()) {
                    return \Redirect::route('reset_password', array($user_id, $resetCode))
                        ->withInput()
                        ->withErrors($validator);
                }

                if ($user->attemptResetPassword($resetCode, $input["new_password"])) {
                    return Redirect::route('sign-in')
                        ->withInput()
                        ->with('success', 'Password has been successfully changed.');

                } else {
                    return Redirect::route('forgot_password')
                        ->withInput()
                        ->with('error', 'An error has occured. Please try again');
                }

            } else {
                return Redirect::route('forgot_password')
                    ->withInput()
                    ->with('error', 'An error has occured. Please try again');
            }
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Redirect::route('forgot_password')
                ->withInput()
                ->with('error', 'An error has occured. Please try again');
        }

    }


}
