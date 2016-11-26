web_folder=/var/www/html/
in_folder=${web_folder}/uploads/
out_folder=${web_folder}/downloads/
while true
do
cd ${in_folder}
for ctl_file in `ls *.ctl 2>/dev/null`
do
base=`echo ${ctl_file} | sed 's/.ctl//g'`
echo ${web_folder}/hide.sh ${in_folder}/${base} ${out_folder}/${base}.pdf
${web_folder}/hide.sh ${in_folder}/${base} ${out_folder}/${base}.pdf
rm -f ${in_folder}/${ctl_file}
chown apache ${out_folder}/*
touch ${out_folder}/${base}.pdf.ctl
done
sleep 1
done
