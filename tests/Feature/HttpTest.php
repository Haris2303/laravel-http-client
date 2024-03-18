<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HttpTest extends TestCase
{
    public function testGet()
    {
        $response = Http::get("https://enk2yfijyps5.x.pipedream.net");
        $this->assertTrue($response->ok());
    }

    public function testPost()
    {
        $response = Http::post("https://enk2yfijyps5.x.pipedream.net");
        $this->assertTrue($response->ok());
    }

    public function testDelete()
    {
        $response = Http::delete("https://enk2yfijyps5.x.pipedream.net");
        $this->assertTrue($response->ok());
    }

    public function testResponse()
    {
        $response = Http::get("https://enk2yfijyps5.x.pipedream.net");
        $this->assertEquals(200, $response->status());
        $this->assertNotNull($response->headers());
        $this->assertNotNull($response->body());

        $json = $response->json();
        $this->assertTrue($json['success']);
    }

    public function testQueryParameter()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10
        ])->get("https://enk2yfijyps5.x.pipedream.net");
        $this->assertEquals(200, $response->status());
    }

    public function testHeader()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10
        ])->withHeaders([
            'Accept' => 'application/json',
            'X-Request-ID' => '12341234'
        ])->get("https://enk2yfijyps5.x.pipedream.net");
        $this->assertEquals(200, $response->status());
    }

    public function testCookie()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10
        ])->withHeaders([
            'Accept' => 'application/json',
            'X-Request-ID' => '12341234'
        ])->withCookies([
            "SessionId" => "123413241",
            "UserId" => "otong"
        ], "enk2yfijyps5.x.pipedream.net")->get("https://enk2yfijyps5.x.pipedream.net");
        $this->assertEquals(200, $response->status());
    }

    public function testFormPost()
    {
        $response = Http::asForm()->post("https://enk2yfijyps5.x.pipedream.net", [
            "username" => "otong",
            "password" => "otong"
        ]);
        $this->assertEquals(200, $response->status());
    }

    public function testMultipart()
    {
        $response = Http::asMultipart()
            ->attach("profile", file_get_contents(__DIR__ . '/HttpTest.php'), "profile.jpg")
            ->post("https://enk2yfijyps5.x.pipedream.net", [
                "username" => "otong",
                "password" => "otong"
            ]);
        $this->assertTrue($response->ok());
    }

    public function testJson()
    {
        $response = Http::asJson()
            ->post("https://enk2yfijyps5.x.pipedream.net", [
                "username" => "otong",
                "password" => "otong"
            ]);
        $this->assertTrue($response->ok());
    }

    public function testTimeout()
    {
        $response = Http::timeout(1)->asJson()
            ->post("https://enk2yfijyps5.x.pipedream.net", [
                "username" => "otong",
                "password" => "otong"
            ]);
        $this->assertTrue($response->ok());
    }

    public function testRetry()
    {
        $response = Http::timeout(5)->retry(5, 1000)->asJson()
            ->post("https://enk2yfijyps5.x.pipedream.net", [
                "username" => "otong",
                "password" => "otong"
            ]);
        $this->assertTrue($response->ok());
    }

    public function testThrowError()
    {
        $this->assertThrows(function () {
            $response = Http::get("https://www.google.com/not-found");
            $this->assertEquals(404, $response->status());
            $response->throw();
        }, RequestException::class);
    }
}
