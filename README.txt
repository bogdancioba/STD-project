Username servers: Bogdan
Password servers: STD#bogdan23

- Am creat doua masini virtuale de tip D2s_v3 (varianta aceasta este minim compatibila)
- Am instalat microk8s pe ambele masini cu urmatoarele comenzi
	sudo apt-get update
	sudo snap install microk8s --classic --channel=1.21
	sudo usermod -a -G microk8s $USER
	sudo chown -f -R $USER ~/.kube
	su - $USER
	microk8s status --wait-ready (asta este pentru verificare daca s-a instalat si functioneaza)
	alias kubectl='microk8s kubectl' (setam un alias pentru a nu scrie de fiecare data comnezi cu 'microk8s kubectl' si scriem cu 'kubectl')
	microk8s enable dns storage
- Am conectat cele doua masini in acelasi cluster de Kubernetes cu urmatoarele comenzi
	microk8s add-node (doar pe server 1 pentru a ne genera o comanda de conectare pe care o dam pe server 2)
	kubectl get node (pe oricare dintre ele pentru a verifica daca s-a realizat conexiunea)
- Am instalat Docker doar pe server 1, pentru ca doar aici avem nevoie sa lansam serviciile
	microk8s enable registry
	curl localhost:32000/v2/_catalog (pentru verificare)
	sudo apt-get install docker.io
- Am lansat un volum persistent pentru MySql (pentru Chat) pe baza fisierului mysql-apache-pvc.yaml (Acesta componenta a sistemului este necesara pentru a oferi serviciului
	Mysql un spatiu de stocare care sa nu fie afectat de dezactivarea/ activarea serviciului)
- Am lansat serviciul de MySql (pentru Chat) pe baza fisierului mysql-apache-deployment.yaml (Acest serviciu este responsabil de stocarea intr-o baza de date a mesajelor
	transmise in cadrul pagini de chat)
- Am creat o baza de date (chatDatabase) si o tabela (Messages) in care se vor stoca textul si timestamp-ul mesajelor din interiorul pod-ului cu urmatoarea
	comanda CREATE TABLE Messages (text NVARCHAR(50),date NVARCHAR(50));
- Am creat fisierele necesare pentru realizarea website-ului pentru chat (index.html, style.css, select.php, post.php). Chat-ul verifica daca exista mesaje noi
	la fiecare secunda si daca exista le incarca facand autoscroll catrea baza.
- Am creat imaginea custom pentru serviciul de chat folosind Dockerfile. Am plecat de la imaginea de baza php:7.4-apache si am adaugat extensiile necesare
	si fisierele pentru chat create anterior. Pentru a face asta am folosit comenzile:
	sudo docker build -t <nume_dorit> . (Cu aceasta comanda se creaza efectiv imagine cu numele ales)
	sudo docker tag <nume_ales> localhost:32000/<nume_ales> (Aceasta comanda atribuie imaginii un tag pentru repository)
	sudo docker push localhost:32000/<nume_ales> (Aceasta comanda incarca imagine in repository)
- Am lansat serviciul de Php-Apache pe baza fisierului phpapache-deployment.yaml (Acest serviciu este responsabil de expunerea website-ului in internet)
- Am lansat serviciul de MySql (pentru CMS) pe baza fisierului mysql-joomla-deployment.yaml (Acest serviciu este responsabil de stocarea intr-o baza de date a datelor pentru
	functionarea CMS-ului, in cazul acesta este vorba despre Joomla)
- Am lansat serviciul de Joomla pe baza fisierului joomla-deployment.yaml (Acest serviciu este responsabil de expunerea CMS-ului Joomla in internet)
- Am accesat Joomla, l-am configurat, am adaugat o poza sugestiva si un IFrame pentru Chat (
		<img width="640" height="530" src="https://s13emagst.akamaized.net/products/32502/32501277/images/res_d5266fea2bd1d275a3928985e005cba0.jpg" alt="PlayStation5">		
		<iframe id="myframe" src="http://172.161.12.200:88" width="640" height="530"></iframe>

		<script>
  			var frame = document.getElementById("myframe");
  			frame.addEventListener("error", function() {
    			frame.src = "http://172.161.10.181:88";
  			});
		</script> )
- Am exportat baza de date joomlaDatabase din pod-ul de MySql (pentru CMS), iar pe baza fisierului exportat si cu ajutorul unui Dockerfile am creat o imagine
	custom pentru serviciul de MySql (pentru CMS) si am modificat fisierul mysql-joomla-deployment.yaml pentru ca serviciul sa se lanseze folosind imaginea
	construita
- Am exportat fisierele de configurare Joomla, iar pe baza acestora am creat si si cu ajutorul unui Dockerfile am creat o imagine
	custom pentru Joomla si am modificat  fisierul joomla-deployment.yaml pentru ca serviciul sa se lanseze folosind imaginea construita
- Am creat un fisier kustomization.yaml cu ajutorul caruia cobor sau ridic toate serviciile cu o singura comanda

	