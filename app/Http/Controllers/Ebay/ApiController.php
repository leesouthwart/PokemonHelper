<?php

namespace App\Http\Controllers\Ebay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    //

    public function test()
    {

        $response = Http::withHeaders([
            'Authorization' => 'v^1.1#i^1#p^1#r^0#f^0#I^3#t^H4sIAAAAAAAAAOVYe2wURRjv9YWILUF52ZR4Lg+j9fZm95679A6OXl9Q+uAqlCZAZnfnetvu7Z47s22vKmlAwAhijEYRDBQJSGJUEiURwT8MakJiQiSISOI/GiMxKkZDaIios3eltJVAoZfYxPvnsjPf983v+833mBnQXzz1sa11W6+UOKbkD/SD/nyHg5sGphYXVZQW5JcV5YERAo6B/gX9hZsKLlZimNRS4iqEU4aOkbM3qelYzAyGGMvURQNiFYs6TCIsElmMRVY2iDwLxJRpEEM2NMZZHw0xvE9BgscbVASAPJI/QEf16zZbjRCjoAAncEGvN+4LAMgLdB5jC9XrmECdUH3Ae1yAd3F8KyeIPq/IATYY5NoZ52pkYtXQqQgLmHAGrpjRNUdgvTVUiDEyCTXChOsjNbGmSH20urG10j3CVniIhxiBxMKjv6oMBTlXQ81Ct14GZ6TFmCXLCGPGHc6uMNqoGLkO5i7gZ6hWFE6QA0oQBoSA7IfenFBZY5hJSG6Nwx5RFVc8IyoinagkfTtGKRtSJ5LJ0FcjNVEfddp/LRbU1LiKzBBTvSyyNtLczIQ1hA2LJHpcMQ1KDSomrtiyNpfEI68P+IHPFRdQ3C9w8aGFstaGaB6zUpWhK6pNGnY2GmQZoqjRWG68I7ihQk16kxmJExvRCDmeu85hwNtub2p2FylM3d5XlKREODOft9+BYW1CTFWyCBq2MHYiQ1GIgamUqjBjJzOxOBQ+vTjEJAhJiW53T08P2+NhDbPDzQPAudtWNsTkBEpChsrauZ6VV2+v4FIzrsiIamJVJOkUxdJLY5UC0DuYsEcQeIEb4n00rPDY0X8NjPDZPTojcpUhXBBCD+QhABApEshJhoSHgtRt40ASTLuS0OxCJKVBGblkGmdWEpmqInp8cd4TjCOX4hfiLq8Qj7skn+J3cXGEAEKSJAvB/1OijDfUY0g2EclJrOcszlc82dK9KtLTsaZG8nU1xKK+RLfS0Bzr5LlETQKuiHYvT/REcLNc29cSGm823NT5Kk2lzLTS9XNBgJ3ruSOhzsAEKRNyLyYbKdRsaKqcnlwb7DGVZmiS9DIrTb9jSNPo34RcjaRS9bmp2Dlz8g6Lxd35nbtO9R91qZt6he3AnVxe2fqYGoAplaV9yM71NCsbSbcB6SbbwxsyqJ1jBG8q5JasNNthIUwoEoWeA8etpNJiztKWpoxfJdswqRPjV6GXDMWSyV0tlOnMLGVT7UgQfEdr9k6EFMnSusavoiCoTShEVXrVmFQBSj3Nuqwq2TsCm/Gbxd0ya9qlyKTXI7bJPjK3Gl1IpwcQYhqahszVE0tWu/QmkxaBkoYmWw3OQS1Saa47BifZCYnzB/y8h/P7wIR8kzPnnw2TrYPkunPewU3IPfpdJpyX+XGbHB+DTY5j+Q4HCAAXVwEeLS54orDgPgbT2sNiqCuS0cuqMM7SsqdDYpmI7ULpFFTN/GKHeuGsPDjiRWhgHZg7/CY0tYCbNuKBCJTfmCnips8p4T2A53hO8Hk50A7m35gt5GYXznxtcJ85MzTv/nvLtsp1y/fWHmphToGSYSGHoyiPhm9e5cyXz89fM/3SwDvmtuTl/p1XZP5y45mQbxWsTIIdpRFpsG/Xi2tPzfjug3Ovv3Fg/h9Fx2va9szduPR86ZLdH36/YPBE03u76jqfW7Pl7NWlnz1fO6Pyd/Fw+GS388LhIwv65tSui9d6fzu+eMrRZEnFgRkHH/i6au/po3sPvWVyzbEK3ylp/+5jDwVmga6FJz/Bfx/8s7ptbvk3X1TMOgLLt2//tmrjI9fORIMvPPX2X7XnP11+z+JXdy4qPSFE13+1+MLmfdceX/IKON0C/Q/vf39L75Wny8s+WvRDWcuvs9M/Xd38o6c31rndqm748t1n1i8c+Nkzr3NHMvrgs5+fu9h+uDrvl5d2XtzWt+dSxZvZbfwHHeh+yasTAAA='
        ])->get('https://api.sandbox.ebay.com/commerce/catalog/v1_beta/product_summary/search?
        q=pokemon psa card&
        limit=1');


        dd($response->body());
    }
}
