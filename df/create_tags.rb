require 'pg'
require 'net/http'
require 'net/https'
require 'uri'
require 'rest-client'
require 'json'
conn = PG::Connection.open(:host=>'localhost',:dbname => 'df',:user=>'postgres',:password=>'varas18')
url_categories='http://df:varas18@localhost/index.php/wp-json/wp/v2/tags'

#creo todas las categrías
	res  = conn.exec('SELECT * FROM datosfreak_tag ORDER BY ID ASC')
	for row in res
		begin
				response = RestClient::Request.new({method: :post,
		      	url: url_categories,
		      	payload: { name: row['nombre'],slug: row['nombre'].downcase.gsub(/\s/,'-')},
		        headers: { :accept => :json, content_type: :json }
		    	}).execute
		rescue Exception => e	
		end
#parent_category_id=JSON.parse(response)
	end
	#por cada categoría, reviso cuál es su categoría padre
	conn.close   

