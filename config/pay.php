<?php

return [
  'alipay' => [
      'app_id'         => '2016091800540236',
      'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1h7CVwL6G41JhOgy8Uxh6cXkMWsAcexVMLuZN5kjAzpelmLMTsuaKGEru5qRi9QQxplVNkbesN0w9F+BVhHSd047mB39DEIOD0tuaYLNqm61MsdtTzN9RrGpqEQ8wKLahUHX/FIlz4W3QlO2yWnJ4BZhybBzL21RqLL4e06BJ3GYq96dOMuHAwgrCrkaMZavxFBJUZecybikCMs69c4QuUbxfoPn5SVXBDcBhpx8CDuirXu/AaGRQ8WnH+ZEhW/ivwz/0dTWFze3XUUFrdOHqYPCy45eQMtoYWdAcs6NG1co5bmlKs1EYQjqtyYPRV1IaTQ4Mpvp4QpHh7zYu8aeuwIDAQAB',
      'private_key'    => 'MIIEowIBAAKCAQEAoTvKjxwxUywpf11rcC0d3AsMpIjq1fpTsTjvAyMkwgRRY03SQywcbaixqef5osDN9ozDORQDkOHKiIGRP3eAiosRzscPsJaFqBH/1j8vQzZVr74Y1KtQ69Iu9XpYr2D//Y+uWx144IHead3ypTX+Iexvu2r1WclizCMqV7TV7o/1TJzumMkGO/+lsHHWDeZUz0j9pE+5cXCTaAMe/HrI12GZ48DRXtpgJlsUqidselT9EJLBIGIeX8gt/S/MfmXqXAJ4bCLCKlKOcwaX8dXgqhEVHXh0p9H/siGe+SdqQ4VM8h1IutqbRoS2+CIf4OlrnQ/PaVPZh8Rj+e72qikyTQIDAQABAoIBAFIlUmhjZsYHZAUeJZT7h5EKmCBkGbF3XpiWdz/T8fBfns/HYG8U1E8SeaIlLEy9irIouXYlOkPgpf2ydbwuOHFdtW7ygI3pwqMEuKIBgyTtU+68HY6M0iez6HTtJq4D76jSQXTuqR1JTXYGvI9r2NSTz8FfFy3tqsZyzCnvWme2ZWoKqPPUkomGZGoZ9aYTqKZwgQadzBZ4CiCxWls4uf4o5fK9YbeyOQHNCoGqPS6XB441TWC6wQIQWfQh2860LumjW/2RQXZVA/4L/XI+akSGy+9C3HWTRDf8yBwYmUOUOtkvUbKqcwlYrQkX9u3lg/IaPXKX0hLy5EltGwxFPUkCgYEA1etA6MdqfInh6/0PUlXDSjKhKDO5YmIVpz3A+qv3GQp4lemN9MgYI6Q3RT2IRI4KmAglH0a4Nkn9E9FYDE2L3BMLYGG/ZJYFrFtThiectD9wr1AtyPTjMpJu5bxO6wrrpiqhm72dWEbe181Bw0sEIcgRgDfa6A3SGTVT7dKVkRsCgYEAwPNab4qjM027qlUUCZGjgOmXqRnOZUHVX+4+zmy8Yd3Y7e4PMQJfa0rA26fGOGaPz2zzcHWb2O3OsLNla+FsDZirPWMOAkyv7CyVnezJfhfYiBp97yZKxpjnu3RRhGr9y0s3M/VnDIi4XI4IhWPjZ06HWiaPbtskDnj3zRsa6LcCgYB/IUR+EqUyRi9BtC7624Un/1bPMY4m7eu4bHbXgoNjGln3ncmDg7b0148RUzQ8qb2aBJ0rLTgk62u/71XIWf2YWKkWKoE4IgBVIiFNRRVX+avaRGgxWXf8ghHt7i9oeCD7q7JHgDSWVefxkEZY7agS+3eLH6a06iHGYW7zk4bXqQKBgQCIsyCMS92J+HWPnM0gZmU/bL99F4JprLWeTG2E0/a3I3SQRQvQPg0aN5DhTkEaLleOrnLeZwRuMTUxbTfasY9bJqGTUT1YlbpBiejKRTsPsZc//fzg1PE/OI9c+HDUbvS19ej7T86dS7PKJvqUfota4oD0dsNB19H4yu1NC/oJiwKBgB4Fa+mj2vjjWMqsW+ngmDBXqIucn7IZ22rpdZGzQ7hPZprhiItoKGeko+IN9NQKwFjzh0b0vqyGLxGJu9zJCmEh1JJS1kgOcoAZ+niMSqiIhCZgxdQ5kV+68BFxAlFq0sl9Gm5zpQkcKWooRiyzQ+MoznesM1WL8kY8SW2T5Pm7',
      'log'            => [
          'file' => storage_path('logs/alipay.log'),
      ],
  ],

  'wechat' => [
      'app_id'      => '',
      'mch_id'      => '',
      'key'         => '',
      'cert_client' => '',
      'cert_key'    => '',
      'log'         => [
          'file' => storage_path('logs/wechat_pay.log'),
      ],
  ],
];