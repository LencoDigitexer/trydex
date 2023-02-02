#!/bin/bash
echo "git pull"
git pull
cd ..
pwd
rm -rf trydex.tk/*
cp -r trydex/* trydex.tk
echo "Done!"
