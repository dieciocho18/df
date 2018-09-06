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

    res  = conn.exec('SELECT * FROM datosfreak_dato ORDER BY ID DESC')
    for row in res

            tags  = conn.exec('SELECT nombre FROM datosfreak_dato_tags dt, datosfreak_tag t WHERE dt.tag_id=t.id and dato_id='+row['id'])
            all_tags_nombres=Array.new
            for tag in tags

                 all_tags_nombres.push(tag['nombre'])
                
            end

            cat  = conn.exec('SELECT c.nombre FROM datosfreak_dato d, datosfreak_categoria c WHERE c.id=d.categoria_id AND d.id='+row['id'])

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
                })
            rescue
            end
        end 
        attachment_id=String.new
        attachment_id=media['attachment_id'] if media
        media=nil

    puts all_tags_nombres.inspect
    if !all_tags_nombres.empty?
begin
        post_id=wp.newPost( :blog_id => "your_blog_id", # 0 unless using WP Multi-Site, then use the blog id
            :content => {
                         :post_status  => "publish",
                         :post_date    => Time.now,
                         :post_content => row['contenido'],
                         :post_title   => row['titulo'],
                         :post_name    => row['slug'],
                         :post_excerpt => row['caption'],
                         :post_date => Date.parse(row['creado']),
                        :post_thumbnail=>attachment_id,
                        :terms_names  => {
                            :category   => [cat[0]['nombre']],
                            :post_tag => all_tags_nombres
                                          },
                        :custom_fields => [{:key => 'fuente',:value =>row['fuente']},
                                           {:key => 'url_video',:value =>row['url_video']},
                                            {:key => 'desmitificacion',:value =>row['desmitificacion']},
                                            {:key => 'es_dato',:value =>row['es_dato']},
                                            {:key => 'es_mito',:value =>row['es_mito']},
                                            {:key => 'fuente_dudosa',:value =>row['fuente_dudosa']}
                                        ]
                        
                         }
            )
        rescue Exception => e   
            puts e.inspect
        end         
 
    else
begin
           post_id=wp.newPost( :blog_id => "your_blog_id", # 0 unless using WP Multi-Site, then use the blog id
            :content => {
                         :post_status  => "publish",
                         :post_date    => Time.now,
                         :post_content => row['contenido'],
                         :post_title   => row['titulo'],
                         :post_name    => row['slug'],
                         :post_excerpt => row['caption'],
                         :post_date => Date.parse(row['creado']),
                         :featured_media=>attachment_id,
                         :terms_names  => {
                            :category   => [cat[0]['nombre']],
                            :post_tag => all_tags_nombres
                                          },
                         :custom_fields => [{:key => 'fuente',:value =>row['fuente']},
                                            {:key => 'url_video',:value =>row['url_video']},
                                            {:key => 'desmitificacion',:value =>row['desmitificacion']},
                                            {:key => 'es_dato',:value =>row['es_dato']},
                                            {:key => 'es_mito',:value =>row['es_mito']},
                                            {:key => 'fuente_dudosa',:value =>row['fuente_dudosa']}
                                            ]
                        }
                            )
        rescue Exception => e   
            puts e.inspect
        end         
    end
               
    end

