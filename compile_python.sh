full_path=$1$2;
counter=0;

printf "array={" > $full_path".response";
> $full_path".error";
printf "  \"input\": {"  > ./tmp/ipt;
printf "  \"output\": {" > ./tmp/opt;
printf "  \"program_output\": {" > ./tmp/popt;
printf "  \"status\": {" >> $full_path".response";

while IFS= read -r ipt && IFS= read -r opt <& 3;
do
  ((counter++))
  printf "    \""$counter"\" : "$ipt"," >> ./tmp/ipt;
  printf "    \""$counter"\" : "$opt"," >> ./tmp/opt;
  program_output=$(echo $ipt | python3 $full_path 2>> $full_path".error");
  printf "    \""$counter"\" : \""$program_output"\"," >> ./tmp/popt;
  if [ -s $full_path".error" ]
  then
    printf "    \""$counter"\" : \"CE\"," >> $full_path".response";
    break;
  fi;
  if [[ $program_output != $opt ]]
  then
    printf "   \""$counter"\" : \"Wrong Answer\"," >> $full_path".response";
    break;
  else
    printf "    \""$counter"\" : \"OK\"," >> $full_path".response";
  fi;
done < input 3< output;

printf "}," >> $full_path".response";
printf "}," >> ./tmp/ipt;
printf "}," >> ./tmp/opt;
printf "}," >> ./tmp/popt;

cat ./tmp/* >> $full_path".response";
printf "}=end" >> $full_path".response";
rm ./tmp/ipt ./tmp/opt ./tmp/popt
