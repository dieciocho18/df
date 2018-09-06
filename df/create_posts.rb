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
conn = PG::Connection.open(:host=>'localhost',:dbname => 'df',:user=>'postgres',:password=>'varas18')
url_posts='http://df:varas18@localhost/index.php/wp-json/wp/v2/posts'
url_category_by_slug='http://df:varas18@localhost/index.php/wp-json/wp/v2/categories?slug='
url_tag_by_slug='http://df:varas18@localhost/index.php/wp-json/wp/v2/tags?slug='




wp = Rubypress::Client.new(:host => "localhost",
                           :username => "df",
                           :password => "varas18")

    res  = conn.exec('SELECT * FROM datosfreak_dato ORDER BY ID ASC')
    for row in res

            tags  = conn.exec('SELECT nombre FROM datosfreak_dato_tags dt, datosfreak_tag t WHERE dt.tag_id=t.id and dato_id='+row['id'])
            all_tags=Array.new
            all_tags_nombres=Array.new
            for tag in tags

                 begin
                response = RestClient::Request.new({method: :get,
                url: url_tag_by_slug+tag['nombre'].downcase.gsub(/\s/,'-'),
                headers: { :accept => :json, content_type: :json }
                }).execute
                rescue Exception => e
                end
                all_tags.push(JSON.parse(response)[0]['id'])
                all_tags_nombres.push(tag['nombre'])
                
            end

            cat  = conn.exec('SELECT c.nombre FROM datosfreak_dato d, datosfreak_categoria c WHERE c.id=d.categoria_id AND d.id='+row['id'])
            begin
                response = RestClient::Request.new({method: :get,
                url: url_category_by_slug+cat[0]['nombre'].downcase.gsub(/\s/,'-'),
                headers: { :accept => :json, content_type: :json }
                }).execute
            rescue Exception => e
            end
            category_id=JSON.parse(response)[0]['id']




        if !row['foto'].empty?

            url='http://www.datosfreak.org/media/'+row['foto']
            puts url
            image_data = open(url)
            nombre = File.basename(row['foto'])
            row['foto']=nil
            File.new(nombre, 'wb') do |file|
              if image_data.respond_to?(:read)
                IO.copy_stream(image_data, file)
              else
                file.write(image_data)
              end
            end
            
            begin
            media=wp.uploadFile(:data => {
                :name => nombre,
                :type => MIME::Types.type_for(nombre).first.to_s,
                :bits =>XMLRPC::Base64.new(File.open(image_data).read)
                #:post_id=>post_id
                })
            rescue
            end
        end 
        puts media['attachment_id']


=begin
                r = RestClient::Request.new({method: :post,
                url: url_posts,
                payload: { 
                    title: row['titulo'],
                    status: 'publish',
                    slug: row['slug'],
                    excerpt:row['caption'],
                    featured_media: media['attachment_id'],
                    content: row['contenido'],
                    categories: [category_id],
                    tags:[all_tags.join(",")]},
                headers: { :accept => :json, content_type: :json }
                })

row['titulo']
puts r.inspect
               puts r.execute
                
        rescue Exception => e   
            puts e.inspect
            puts r.to_s
=end

#row['titulo']

begin
    puts all_tags_nombres.inspect
        post_id=wp.newPost( :blog_id => "your_blog_id", # 0 unless using WP Multi-Site, then use the blog id
            :content => {
                         :post_status  => "publish",
                         :post_date    => Time.now,
                         :post_content => row['contenido'],
                         :post_title   => row['titulo'],
                         :post_name    => row['slug'],
                         :post_excerpt => row['caption'],
                       #  :post_author  => 1, # 1 if there is only the admin user, otherwise the user's id
                       :featured_media=>media['attachment_id'],
                       :terms_names  => {
                            :category   => [cat[0]['nombre']],
                            :post_tag => [all_tags_nombres.join(",")]
                                          }
                        
                         }
            )
    rescue Exception => e   
        puts 'dsa'
        puts e.inspect
end         
puts 'POST'
puts post_id

               
        #attachment_id=media['attachment_id']
=begin
                response = RestClient::Request.new({method: :post,
                url: url_posts,
                payload: { title: row['titulo'],slug: row['slug'],status:'publish',content:['cotenido']},
                headers: { :accept => :json, content_type: :json }
                }).execute
        rescue Exception => e   
=end    
    end

