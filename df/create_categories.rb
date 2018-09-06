require 'pg'
require 'net/http'
require 'net/https'
require 'uri'
require 'rest-client'
require 'json'
conn = PG::Connection.open(:host=>'localhost',:dbname => 'df',:user=>'postgres',:password=>'varas18')
url_categories='http://df:varas18@localhost/index.php/wp-json/wp/v2/categories?per_page=100'
url_category_by_slug='http://df:varas18@localhost/index.php/wp-json/wp/v2/categories?slug='


#creo todas las categrías

	res  = conn.exec('SELECT * FROM datosfreak_categoria ORDER BY ID ASC')

	for row in res

		begin
				response = RestClient::Request.new({method: :post,
		      	url: url_categories,
		      	payload: { name: row['nombre']},
		        headers: { :accept => :json, content_type: :json }
		    	}).execute
		rescue Exception => e	
		end

		if response
			puts 'CATEGORIA'
			puts response
			category_id=JSON.parse(response)['id']
			puts category_id
		end
		if row['padre_id']
			res_parent  = conn.exec('SELECT nombre FROM datosfreak_categoria WHERE id='+row['padre_id'])
			category_slug=res_parent[0]['nombre'].downcase.gsub(/\s/,'-')
 			puts 'CATEGORIA PADRE'
 			puts row['padre_id']
 			puts category_slug
			begin
				response = RestClient::Request.new({method: :get,
		      	url: url_category_by_slug+category_slug,
		        headers: { :accept => :json, content_type: :json }
		    	}).execute
			rescue Exception => e
			end
#update category
			parent_id=JSON.parse(response)[0]['id']
			puts parent_id
			begin
				update_url='http://df:varas18@localhost/index.php/wp-json/wp/v2/categories/'+category_id.to_s
				puts update_url
				response = RestClient::Request.new({method: :post,
		      	url: update_url,
		      	payload: { parent: parent_id},
		        headers: { :accept => :json, content_type: :json }
		    	}).execute
		 	rescue Exception => e
			end
			puts 'UPDATE CATEGORY'
			puts JSON.parse(response).to_s
		end
#parent_category_id=JSON.parse(response)
	end
	#por cada categoría, reviso cuál es su categoría padre


		

	conn.close   

