import jwt from 'jsonwebtoken';

// don't fortget to generate your own private and public key
// by openssl: https://gist.github.com/MeetMartin/8b3e72e4b94cdc6d063671a69440a7e2

const privateKey =
`-----BEGIN EC PRIVATE KEY-----
MIHcAgEBBEIANjT0EzYXVqsHeKitVMvAQ57pzZWcv5QEWsCap4hl3mk/DIkRCYzg
9YGcBtLWhuNitEeKFGLi91rohc2EzmTVIbOgBwYFK4EEACOhgYkDgYYABAFjzu7a
UwPT7fIFtwc89Vrkj4a1iXOYNhWEZ97V2EbpFq3AU28o7MoV+IbSv/VrGsHA/1SD
OOFabnyu3QbVxOywXQAEOowT5Hw6ue+ULtRqQ7m7gs1UMyaf6k9Sqv1zxkrNoWBA
OzMWISxnOoNE3Zn3jaUTv9jBSki5mheMALDaSoIGxw==
-----END EC PRIVATE KEY-----
`;
const publicKey =
`-----BEGIN PUBLIC KEY-----
MIGbMBAGByqGSM49AgEGBSuBBAAjA4GGAAQBY87u2lMD0+3yBbcHPPVa5I+GtYlz
mDYVhGfe1dhG6RatwFNvKOzKFfiG0r/1axrBwP9UgzjhWm58rt0G1cTssF0ABDqM
E+R8OrnvlC7UakO5u4LNVDMmn+pPUqr9c8ZKzaFgQDszFiEsZzqDRN2Z942lE7/Y
wUpIuZoXjACw2kqCBsc=
-----END PUBLIC KEY-----
`;

const data = {
    iss: 'https://domain.tld',
    sub: 'martin@mail.tld',
    aud: 'https://domain.tld',
    exp: Math.floor(Date.now() / 1000) + (60 * 60), // 1 hour
    nbf: Math.floor(Date.now() / 1000),
    jti: 'asfasgsadg',
    data: { username: 'awesome' }
};

const encrypted = jwt.sign(data, privateKey, { algorithm: 'ES512' });
console.log('encrypted', encrypted);
/* base64 decoded token is
{"alg":"ES512","typ":"JWT"}{"iss":"https://domain.tld","sub":"martin@mail.tld","aud":"https://domain.tld","exp":1630993148,"nbf":1630989548,"jti":"asfasgsadg","data":{"some":"information"},"iat":1630989548}Y?ykԒFjp'9ܮjv]]_ߜB~%X oX3@'VB=eqU&}TY%̒!%mLF
claims are not encrypted without combining JWT with JWE
*/

const decrypted = jwt.verify(encrypted, publicKey, { algorithms: ['ES512'], audience: 'https://domain.tld', issuer: 'https://domain.tld' });
console.log('decrypted', decrypted);
// verify throws error if token is expired or some other claim fails
// you should verify sub and jti by your own logic

/* script output:
encrypted eyJhbGciOiJFUzUxMiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2RpZGF1dGgubWVldC1tYXJ0aW4uY29tIiwic3ViIjoiZGlkOmtleTphc2dhc2dhcyIsImF1ZCI6Imh0dHBzOi8vZGlkYXV0aC5tZWV0LW1hcnRpbi5jb20iLCJleHAiOjE2MzA5ODQ5NjksIm5iZiI6MTYzMDk4MTM2OSwianRpIjoiYXNmYXNnc2FkZyIsImlhdCI6MTYzMDk4MTM2OX0.AVzU4urVb4ibEmblnrohDHRvIQmYSx-Hzc2xixqXtmlDm34I8CSLJ-lgS8ORAFZFV0xnr7JIeeXuQIjRs-Pte5fyAQjzlds0C_PjUFbWSnyhsG6zQWUJVZknlTuqR69Q_SuMAW3sknaR8E2pEZZYsoc4Jvlar85h-24w-3Aj1_uKIxIj
decrypted {
  iss: 'https://domain.tld',
  sub: 'martin@mail.tld',
  aud: 'https://domain.tld',
  exp: 1630984969,
  nbf: 1630981369,
  jti: 'asfasgsadg',
  iat: 1630981369
}
*/