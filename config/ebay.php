<?php

// Ebay API Settings
return [
    //
    'app_id' => env('ebay_app_id', null),
    'dev_id' => env('ebay_dev_id', null),
    'cert_id' => env('ebay_cert_id', null),
    'test_sandbox_login' => env('sandbox_base', null) . urlencode(env('sandbox_test_login_scopes')),
    'prod_login'=> env('production_base', null) . urlencode(env('production_login_scopes')),
    'test_oauth_app_token' => 'v^1.1#i^1#p^1#r^0#f^0#I^3#t^H4sIAAAAAAAAAOVYXWwUVRTubH8ItPxIazWisg5KwLqzM7O7050Ju7JtKVQKLWxBqBByZ+ZOd9rZmWXunbZrIJRajAQSEmN8UGMaECISxfiggILRKMaID/4AkWACPhjQCBIi+PfgnWkp20qg0E1s4rxM7r3nnHvOd/7uvWxPycRHn1307NXJ1ARffw/b46MorpSdWFJcNaXQd19xAZtDQPX3PNxT1Ft4bh4CaSMjLYcoY5kI+rvThokkbzJGO7YpWQDpSDJBGiIJK1IysaRR4hlWytgWthTLoP0NdTFaENRoSOA1IVQdiWoimTSviWyxyLIqilpIifByVI5CMULWEXJgg4kwMHGM5lk+FGD5AMe3cKIUikosx3DhcCvtXwltpFsmIWFYOu5pK3m8do6qN9cUIARtTITQ8YZEfbIp0VC3YGnLvGCOrPggDEkMsIOGj2otFfpXAsOBN98GedRS0lEUiBAdjA/sMFyolLimzB2o7yGtCiFVqObDAIT5qArCeYGy3rLTAN9cD3dGVwOaRypBE+s4eytECRpyO1Tw4GgpEdFQ53d/yxxg6JoO7Ri9oCaxOtHcTMcNiCwHp7oCSQPIjTrCgWTNqoDMw3CEFdhIQBOhJoicNrjRgLRBmEfsVGuZqu6ChvxLLVwDidZwJDZ8DjaEqMlsshMadjXKoeO5axiGoq2uUwe8SNQ0Xb/CNAHC7w1v7YEhboxtXXYwHJIwcsGDKEaDTEZX6ZGLXiwOhk83itEpjDNSMNjV1cV0hRjLbgvyLMsFVy1pTCopmAa0S+vmukev35ohoHumKJBwIl3C2QzRpZvEKlHAbKPjIVHkRW4Q9+FqxUfO/msix+bg8IzIV4ZokTArKHwYairLRhUuHxkSHwzSoKsHlEE2kAZ2B8QZAygwoJA4c9LQ1lUpFNH4UFSDAVUQtUBY1LSAHFGFAKdByEIoy4oY/T8lymhDPQkVG+L8xHq+4nzx+mWdyxNdbU/Wy5GOxmRdJNWpNjYn23kuVZ8Ci+s6n0h1JVCzsvDpZbHRZsMNja81dIJMC9k/LwC4uZ43EBZZCEN1TOYlFSsDmy1DV7Ljy8EhW20GNs7WOFkyTkLDIL8xmZrIZBryVLHzZeRtFos7szuPneq/6VI3tAq5gTu+rHL5EREAMjrj9iE31xnFSgctQJzsTq/ztPaPJLwRUVB2skybAxEmmqjkHDhqJp0Uc4a0NHX0LAMNkxgxehZyx1AdBd/RRl5nZgiaelsKo9vas3ssoMiO0TF6FhUCY0whqpOrxrgKUGLpgMm6OnBHYDy7GdSpMLZbimxyPWKa3CNzi9UBTXIAwbZlGNBeObZkdUtvOu1gIBtwvNXgPNQiHeCiXurq+LKLE6oFPsQK0bHZpnjnn3XjrYPku3Pexk0oOPxZJl7gfVwvdZjtpQ76KIqtZgNcFTu3pHBFUWEZjUjtYRAwVdnqZnSgMaTsmQA7NmQ6YDYDdNtXQumnvlV+z3kQ6l/L3jv0JDSxkCvNeR9i77++UsxNvWcycTPP8ZwYirJcKzvr+moRV1lU0emj2s/6f9a3xeiTrReOdb9cccliJw8RUVRxAYnegtqCnT+c+Sy7KG1fNc7ffenH3/qoD5r6Vm+rvcj0T9mzIPHAicpJb7/OHevftNl+Lz1h64y//5xamTk+/ey0gp6qM51fn//lxQ9XJxcm1gindsy8nCws23Lu0PaHyl8oKimFtUtKP559Zv2+LUc/qniuZcOB+Rs3TtpzZZOQ7Wu7KJad/nz295++suvXK4dm6PTcP3a/tWLC1P3fde+6uPf45jVbT4LI6Tnbn39X2/9TReWDHS9Nnx+Ttz/zja/8kRMHm8RI84Etj6utX2zdt7bmsbPT7lonvjnz+IZXj3751F/zP9mNX9sV27tpS1VNVWzGjsN0+YXllw9OOvIO1TvryM6+90/tmON8Vd7aXvaGc/SCb8CN/wDWaPZpqhMAAA=='
];
