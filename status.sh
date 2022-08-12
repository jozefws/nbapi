for i in {1..10}
do
    landscape-sysinfo --exclude-sysinfo-plugins LoggedInUsers,Network,Processes
    echo " "
    sleep 1
done