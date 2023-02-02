#!/bin/bash
echo "git pull"
git pull
cd ..
pwd
echo "Delete old site"
rm -rf trydex.tk/*
echo "Copy new site"
cp -r trydex/* trydex.tk
echo "Done!"
