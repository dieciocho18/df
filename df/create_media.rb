require 'pg'
require 'data_uri'
require 'rubypress'
require 'net/http'
require 'net/https'
require 'uri'
require 'rest-client'
require 'json'
require 'base64'
require "open-uri"

wp = Rubypress::Client.new(:host => "localhost",
                           :username => "df",
                           :password => "varas18")

url='http://www.datosfreak.org/media/upload/Transmision-oral-de-la-Tora.jpg'

image_data = open(url)
        nombre = 'ransmision-oral-de-la-Tora.jpg'
        File.new(nombre, 'wb') do |file|
          if image_data.respond_to?(:read)
            IO.copy_stream(image_data, file)
          else
            file.write(image_data)
          end
        end
        media=wp.uploadFile(:data => {
            :name => nombre,
            :type => MIME::Types.type_for(nombre).first.to_s,
            :bits =>XMLRPC::Base64.new(File.open(image_data).read),
            :post_id=>5094
            })
puts media['attachment_id']