<?php 

namespace App\OAuth\Grant;

use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Bridge\User as UserEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;

class PasswordOverrideGrant extends PasswordGrant
{
    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return 'password_override';
    }

    /**
     * {@inheritdoc}
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $username = $this->getParameter('username', $request);
        $password = $this->getParameter('password', $request);

        $user = User::where(['username' => $username])->first();

        if ($user) {
            $validCredentials = Hash::check($password, $user->password);

            if (!$validCredentials) {
                throw OAuthServerException::invalidCredentials();
            }
        }

        if ($user instanceof Authenticatable) {
            $user = new UserEntity($user->getAuthIdentifier());
        }

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    /**
     * @var string $param
     * @var ServerRequestInterface $request
     * @var bool $required
     *
     * @return string
     *
     * @throws OAuthServerException
     */
    protected function getParameter($param, ServerRequestInterface $request, $required = true)
    {
        $value = $this->getRequestParameter($param, $request);

        if (is_null($value) && $required) {
            throw OAuthServerException::invalidRequest($param);
        }

        return $value;
    }
}
