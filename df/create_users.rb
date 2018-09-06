require 'pg'
require 'net/http'
require 'net/https'
require 'uri'
require 'rest-client'
require 'mysql2'


	#con = PGconnection.new('localhost', 5432, '', '', 'df','postgres', 'varas18')

	conn = PG::Connection.open(:host=>'localhost',:dbname => 'df',:user=>'postgres',:password=>'varas18')
	#conn_mysql = Mysql2::Connection.open(:host=>'localhost',:dbname => 'wp',:user=>'wp',:password=>'varas18')
client_mysql = Mysql2::Client.new(:host => "localhost", :username => "wp",:password=>'varas18',:database => 'wp')
results_notas = conn.exec("SELECT slug, cantidad ,fecha, username,nota.autor_id, nota.dato_id FROM auth_user u, datosfreak_dato dato, datosfreak_nota nota
WHERE dato.id=nota.dato_id AND u.id=nota.autor_id")
#

	url='http://df:varas18@localhost/index.php/wp-json/wp/v2/users/'
	user='df'
	pass='varas18'
	res  = conn.exec('SELECT * FROM auth_user ORDER BY ID')
	#res_2  = conn_mysql.exec('')
res=nil

=begin
	for row in res
			puts row['username']
		begin
			
		
			response = RestClient::Request.new({method: :post,
	      	url: url,
	      	user: user,
	      	password: pass,
	      	payload: { username: row['username'], email: row['email'],password: row['password'],role: 'subscriber' },
	        headers: { :accept => :json, content_type: :json }
	    	}).execute
		rescue Exception => e
			
		end
  	end
=end
  	for nota in results_notas
  		results = client_mysql.query("SELECT * FROM wp.wp_users WHERE user_nicename='"+nota['username']+"'")
		autor_id=1
		autor_id=results.first['ID'] if results.first
 		results = client_mysql.query("SELECT * FROM wp.wp_posts WHERE post_name='"+nota['slug']+"'")
		post_id=nil
		post_id=results.first['ID'] if results.first
		if post_id
			results = client_mysql.query("
			INSERT INTO wp.wp_yasr_log
			(post_id,multi_set_id,user_id,vote,date,ip)
			VALUES
			("+post_id.to_s+",-1,"+autor_id.to_s+","+nota['cantidad'].to_i.to_s+",now(),1)")
		end
		
	end



