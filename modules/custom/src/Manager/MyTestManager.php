<?php

namespace Drupal\my_test\Manager;

/**
 * Class TestManager.
 *
 * @package Drupal\my_test\Manager
 */
class TestManager {

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs event subscriber.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * Encrypt data using a specified key and encode with Base64URL.
   *
   * @param string $data
   *   The data to encrypt.
   *
   * @return string
   *   The encrypted and encoded data.
   */
  private function encrypt_data($data) {
    $method = 'aes-256-cbc';
    $key = 'HereWeAre';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    return rtrim(strtr(base64_encode($iv . $encrypted), '+/', '-_'), '=');
  }

  /**
   * Decrypt data using a specified key and decode from Base64URL.
   *
   * @param string $data
   *   The data to decrypt.
   *
   * @return string
   *   The decrypted data.
   */
  private function decrypt_data($data) {
    $method = 'aes-256-cbc';
    $key = 'HereWeAre';
    $data = base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    $iv_length = openssl_cipher_iv_length($method);
    $iv = substr($data, 0, $iv_length);
    $encrypted_data = substr($data, $iv_length);
    return openssl_decrypt($encrypted_data, $method, $key, 0, $iv);
  }

}
