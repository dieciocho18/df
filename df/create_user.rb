require 'net/http'
require 'net/https'
require 'uri'
require 'rest-client'
require 'json'


url='http://df:varas18@localhost/index.php/wp-json/wp/v2/users/'

user='df'
pass='varas18'

response = RestClient::Request.new({
      method: :post,
      url: url,
      user: user,
      password: pass,
      payload: { username: 'somevalue', email: 'other@value.cl',password: 'dqfklqenflDWEDWE23wdqw' },
            headers: { :accept => :json, content_type: :json }
    }).execute do |response, request, result|
      case response.code
      when 400
        puts response.to_str
        [ :error, (response.to_str) ]
      when 200
        [ :success, (response.to_str) ]
      else
        fail "Invalid response #{response.to_str} received."
      end
    end

#response = resource.post(:username=>'asd',:email=>'sade@de.cl',:password=>'dewwewe'